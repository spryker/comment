<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentTagTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;

interface CommentEntityManagerInterface
{
    public function createCommentThread(CommentThreadTransfer $commentThreadTransfer): CommentThreadTransfer;

    public function createComment(CommentTransfer $commentTransfer): CommentTransfer;

    public function updateComment(CommentTransfer $commentTransfer): CommentTransfer;

    public function removeComment(CommentTransfer $commentTransfer): void;

    public function addCommentTagsToComment(CommentTransfer $commentTransfer): void;

    public function removeCommentTagsFromComment(CommentTransfer $commentTransfer): void;

    public function createCommentTag(CommentTagTransfer $commentTagTransfer): CommentTagTransfer;

    public function removeCommentThread(int $idCommentThread): void;
}
