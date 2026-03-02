<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 * @method \Spryker\Zed\Comment\Communication\CommentCommunicationFactory getFactory()
 */
class GatewayController extends AbstractGatewayController
{
    public function addCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->addComment($commentRequestTransfer);
    }

    public function updateCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->updateComment($commentRequestTransfer);
    }

    public function removeCommentAction(CommentRequestTransfer $commentRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->removeComment($commentRequestTransfer);
    }

    public function addCommentTagAction(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->addCommentTag($commentTagRequestTransfer);
    }

    public function removeCommentTagAction(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        return $this->getFacade()->removeCommentTag($commentTagRequestTransfer);
    }
}
