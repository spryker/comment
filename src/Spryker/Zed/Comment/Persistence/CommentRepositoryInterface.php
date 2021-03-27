<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;

interface CommentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentsRequestTransfer $commentsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer[]
     */
    public function getCommentThreads(CommentsRequestTransfer $commentsRequestTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function findCommentsByCommentThread(CommentThreadTransfer $commentThreadTransfer): array;

    /**
     * @param int[] $threadIds
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function findCommentsByCommentThreadIds(array $threadIds): array;

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    public function findCommentByUuid(CommentTransfer $commentTransfer): ?CommentTransfer;

    /**
     * @return \Generated\Shared\Transfer\CommentTagTransfer[]
     */
    public function getAllCommentTags(): array;

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function getCommentsByFilter(CommentFilterTransfer $commentFilterTransfer): array;
}
