<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Comment\Persistence\CommentPersistenceFactory getFactory()
 */
class CommentRepository extends AbstractRepository implements CommentRepositoryInterface
{
    /**
     * @module Customer
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThread(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        $commentRequestTransfer
            ->requireOwnerId()
            ->requireOwnerType();

        $commentThreadEntity = $this->getFactory()
            ->getCommentThreadPropelQuery()
            ->filterByOwnerId($commentRequestTransfer->getOwnerId())
            ->filterByOwnerType($commentRequestTransfer->getOwnerType())
            ->joinWithSpyComment()
            ->useSpyCommentQuery()
                ->joinWithSpyCustomer()
                ->leftJoinWithSpyCommentCommentTag()
            ->endUse()
            ->find()
            ->getFirst();

        if (!$commentThreadEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCommentMapper()
            ->mapCommentThreadEntityToCommentThreadTransfer($commentThreadEntity, new CommentThreadTransfer());
    }

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    public function findCommentByUuid(string $uuid): ?CommentTransfer
    {
        $commentEntity = $this->getFactory()
            ->getCommentPropelQuery()
            ->filterByUuid($uuid)
            ->findOne();

        if (!$commentEntity) {
            return null;
        }

        return $this->getFactory()
            ->createCommentMapper()
            ->mapCommentEntityToCommentTransfer($commentEntity, new CommentTransfer());
    }
}
