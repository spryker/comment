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
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CommentThreadWriterInterface
{
    public function createCommentThread(CommentRequestTransfer $commentRequestTransfer): CommentThreadTransfer;

    public function duplicateCommentThread(
        CommentFilterTransfer $commentFilterTransfer,
        CommentRequestTransfer $commentRequestTransfer,
        ?bool $forceDelete = false
    ): CommentThreadResponseTransfer;

    public function copyCommentThreadFromOrderToQuote(
        OrderTransfer $orderTransfer,
        QuoteTransfer $quoteTransfer
    ): CommentThreadResponseTransfer;
}
