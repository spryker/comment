<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Reader;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;

interface CommentThreadReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer;

    /**
     * @param \Generated\Shared\Transfer\CommentsRequestTransfer $commentsRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CommentThreadTransfer>
     */
    public function getCommentThreads(CommentsRequestTransfer $commentsRequestTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer;
}
