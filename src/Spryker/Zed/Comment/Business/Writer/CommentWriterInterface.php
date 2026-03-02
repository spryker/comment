<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Writer;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;

interface CommentWriterInterface
{
    public function addComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function updateComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;

    public function removeComment(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer;
}
