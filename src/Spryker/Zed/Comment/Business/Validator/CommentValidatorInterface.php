<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;

interface CommentValidatorInterface
{
    public function validateCommentRequestOnCreate(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer;

    public function validateCommentRequestOnUpdate(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentValidationResponseTransfer;

    public function validateCommentRequestOnDelete(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentValidationResponseTransfer;

    public function validateCommentAuthor(
        CommentRequestTransfer $commentRequestTransfer
    ): CommentValidationResponseTransfer;
}
