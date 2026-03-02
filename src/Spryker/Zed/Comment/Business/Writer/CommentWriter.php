<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface;
use Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface;
use Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CommentWriter implements CommentWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface
     */
    protected $commentEntityManager;

    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var \Spryker\Zed\Comment\Business\Reader\CommentThreadReaderInterface
     */
    protected $commentThreadReader;

    /**
     * @var \Spryker\Zed\Comment\Business\Writer\CommentThreadWriterInterface
     */
    protected $commentThreadWriter;

    /**
     * @var \Spryker\Zed\Comment\Business\Validator\CommentValidatorInterface
     */
    protected $commentValidator;

    public function __construct(
        CommentEntityManagerInterface $commentEntityManager,
        CommentRepositoryInterface $commentRepository,
        CommentThreadReaderInterface $commentThreadReader,
        CommentThreadWriterInterface $commentThreadWriter,
        CommentValidatorInterface $commentValidator
    ) {
        $this->commentEntityManager = $commentEntityManager;
        $this->commentRepository = $commentRepository;
        $this->commentThreadReader = $commentThreadReader;
        $this->commentThreadWriter = $commentThreadWriter;
        $this->commentValidator = $commentValidator;
    }

    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType()
            ->requireComment()
            ->getComment()
            ->requireMessage();

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentAuthor($commentRequestTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createCommentThreadResponseTransferWithErrors($commentValidationResponseTransfer);
        }

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnCreate($commentRequestTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createCommentThreadResponseTransferWithErrors($commentValidationResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer) {
            return $this->executeAddCommentTransaction($commentRequestTransfer);
        });
    }

    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
            ->requireUuid()
            ->requireMessage();

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentAuthor($commentRequestTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createCommentThreadResponseTransferWithErrors($commentValidationResponseTransfer);
        }

        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnUpdate($commentRequestTransfer, $commentTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createCommentThreadResponseTransferWithErrors($commentValidationResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentRequestTransfer, $commentTransfer) {
            return $this->executeUpdateCommentTransaction($commentRequestTransfer, $commentTransfer);
        });
    }

    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentRequestTransfer
            ->requireComment()
            ->getComment()
            ->requireUuid();

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentAuthor($commentRequestTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createCommentThreadResponseTransferWithErrors($commentValidationResponseTransfer);
        }

        $commentTransfer = $this->commentRepository->findCommentByUuid($commentRequestTransfer->getComment());

        $commentValidationResponseTransfer = $this->commentValidator->validateCommentRequestOnDelete($commentRequestTransfer, $commentTransfer);
        if (!$commentValidationResponseTransfer->getIsSuccessfulOrFail()) {
            return (new CommentThreadResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages($commentValidationResponseTransfer->getMessages());
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($commentTransfer) {
            return $this->executeRemoveCommentTransaction($commentTransfer);
        });
    }

    protected function executeAddCommentTransaction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        $commentTransfer = $commentRequestTransfer->getComment();
        $commentTransfer
            ->setMessage(trim($commentTransfer->getMessage()))
            ->setIsUpdated(false);

        $commentThreadTransfer = $this->getCommentThread($commentRequestTransfer);
        $commentTransfer->setIdCommentThread($commentThreadTransfer->getIdCommentThread());

        $this->commentEntityManager->createComment($commentTransfer);
        $commentThreadTransfer->addComment($commentTransfer);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    protected function executeUpdateCommentTransaction(
        CommentRequestTransfer $commentRequestTransfer,
        CommentTransfer $commentTransfer
    ): CommentThreadResponseTransfer {
        $commentTransfer
            ->setMessage(trim($commentRequestTransfer->getComment()->getMessage()))
            ->setCommentTags($commentRequestTransfer->getComment()->getCommentTags())
            ->setIsUpdated(true);

        $commentTransfer = $this->commentEntityManager->updateComment($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    protected function executeRemoveCommentTransaction(
        CommentTransfer $commentTransfer
    ): CommentThreadResponseTransfer {
        $commentTransfer->setCommentTags(new ArrayObject());
        $this->commentEntityManager->removeCommentTagsFromComment($commentTransfer);
        $this->commentEntityManager->removeComment($commentTransfer);

        return $this->createCommentThreadResponse($commentTransfer);
    }

    protected function getCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentThreadReader->findCommentThreadByOwner($commentRequestTransfer);

        if ($commentThreadTransfer) {
            return $commentThreadTransfer;
        }

        return $this->commentThreadWriter->createCommentThread($commentRequestTransfer);
    }

    protected function createCommentThreadResponse(CommentTransfer $commentTransfer): CommentThreadResponseTransfer
    {
        $commentThreadTransfer = (new CommentThreadTransfer())
            ->setIdCommentThread($commentTransfer->getIdCommentThread());

        $commentThreadTransfer = $this->commentThreadReader->findCommentThreadById($commentThreadTransfer);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(true)
            ->setCommentThread($commentThreadTransfer);
    }

    protected function createCommentThreadResponseTransferWithErrors(
        CommentValidationResponseTransfer $commentValidationResponseTransfer
    ): CommentThreadResponseTransfer {
        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false)
            ->setMessages($commentValidationResponseTransfer->getMessages());
    }
}
