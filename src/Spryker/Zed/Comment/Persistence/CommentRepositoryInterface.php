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
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentsRequestTransfer $commentsRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CommentThreadTransfer>
     */
    public function getCommentThreads(CommentsRequestTransfer $commentsRequestTransfer): array;

    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function findCommentsByCommentThread(CommentThreadTransfer $commentThreadTransfer): array;

    /**
     * @param array<int> $threadIds
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function getCommentsByCommentThreadIds(array $threadIds): array;

    public function findCommentByUuid(CommentTransfer $commentTransfer): ?CommentTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\CommentTagTransfer>
     */
    public function getAllCommentTags(): array;

    /**
     * @param \Generated\Shared\Transfer\CommentFilterTransfer $commentFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\CommentTransfer>
     */
    public function getCommentsByFilter(CommentFilterTransfer $commentFilterTransfer): array;

    public function isCustomerCommentAuthor(int $idCustomer, int $idComment): bool;
}
