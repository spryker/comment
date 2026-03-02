<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentThreadWriter implements CommentThreadWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS = 'comment.validation.error.comment_thread_already_exists';

    /**
     * @uses \Spryker\Zed\Comment\Communication\Plugin\Quote\CommentThreadQuoteExpanderPlugin::COMMENT_THREAD_QUOTE_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var \Spryker\Zed\Comment\Business\Writer\CommentTagWriterInterface
     */
    protected $commentTagWriter;

    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentTagWriterInterface $commentTagWriter
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentTagWriter = $commentTagWriter;
    }

    public function createCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentThreadTransfer = (new CommentThreadTransfer())
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        return $this->commentEntityManager->createCommentThread($commentThreadTransfer);
    }

    public function duplicateCommentThread(
        CommentFilterTransfer $commentFilterTransfer,
        CommentRequestTransfer $commentRequestTransfer,
        ?bool $forceDelete = false
    ): CommentThreadResponseTransfer {
        $commentFilterTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentFilterTransfer, $commentRequestTransfer, $forceDelete) {
            return $this->executeDuplicateCommentThreadTransaction($commentFilterTransfer, $commentRequestTransfer, $forceDelete);
        });
    }

    public function copyCommentThreadFromOrderToQuote(
        OrderTransfer $orderTransfer,
        QuoteTransfer $quoteTransfer
    ): CommentThreadResponseTransfer {
        $commentThreadTransfer = $orderTransfer->getCommentThread();
        $commentThreadResponseTransfer = (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false);

        if (!$commentThreadTransfer || !$commentThreadTransfer->getComments()->count()) {
            return $commentThreadResponseTransfer;
        }

        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($commentThreadTransfer->getOwnerIdOrFail())
            ->setOwnerType($commentThreadTransfer->getOwnerTypeOrFail());

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuoteOrFail())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE);

        return $this->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }

    protected function executeDuplicateCommentThreadTransaction(
        CommentFilterTransfer $commentFilterTransfer,
        CommentRequestTransfer $commentRequestTransfer,
        ?bool $forceDelete = false
    ): CommentThreadResponseTransfer {
        $persistedCommentThread = $this->commentRepository->findCommentThread($commentRequestTransfer);
        if ($persistedCommentThread && !$forceDelete) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS);
        }

        if ($persistedCommentThread) {
            $this->commentEntityManager->removeCommentThread($persistedCommentThread->getIdCommentThreadOrFail());
        }

        $commentThreadTransfer = $this->createCommentThread($commentRequestTransfer);
        $commentTransfers = $this->commentRepository->getCommentsByFilter($commentFilterTransfer);

        foreach ($commentTransfers as $commentTransfer) {
            $commentThreadTransfer->addComment(
                $this->duplicateComment($commentTransfer, $commentThreadTransfer),
            );
        }

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    protected function duplicateComment(CommentTransfer $commentTransfer, CommentThreadTransfer $commentThreadTransfer): CommentTransfer
    {
        $duplicatedCommentTransfer = (new CommentTransfer())
            ->fromArray($commentTransfer->toArray())
            ->setIdCommentThread($commentThreadTransfer->getIdCommentThread())
            ->setIdComment(null)
            ->setUuid(null)
            ->setCreatedAt(null)
            ->setUpdatedAt(null);

        $duplicatedCommentTransfer = $this->commentEntityManager->createComment($duplicatedCommentTransfer);

        if ($duplicatedCommentTransfer->getCommentTags()->count()) {
            $this->commentTagWriter->saveCommentTags($duplicatedCommentTransfer);
        }

        return $duplicatedCommentTransfer;
    }

    protected function createErrorResponse(string $message): CommentThreadResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
