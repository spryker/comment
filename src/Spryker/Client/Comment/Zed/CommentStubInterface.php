<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment\Zed;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;

interface CommentStubInterface
{
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function updateCommentTags(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer;

    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer;
}
