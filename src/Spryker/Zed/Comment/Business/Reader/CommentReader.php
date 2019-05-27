<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Reader;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;

class CommentReader implements CommentReaderInterface
{
    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        $commentRequestTransfer
            ->requireIdOwner()
            ->requireOwnerType();

        return $this->commentRepository->findCommentThread($commentRequestTransfer);
    }
}
