<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence;

use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Orm\Zed\Comment\Persistence\SpyCommentTagQuery;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery;
use Spryker\Zed\Comment\Persistence\Propel\Mapper\CommentMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 * @method \Spryker\Zed\Comment\Persistence\CommentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface getRepository()
 */
class CommentPersistenceFactory extends AbstractPersistenceFactory
{
    public function getCommentThreadPropelQuery(): SpyCommentThreadQuery
    {
        return SpyCommentThreadQuery::create();
    }

    public function getCommentPropelQuery(): SpyCommentQuery
    {
        return SpyCommentQuery::create();
    }

    public function getCommentToCommentTagPropelQuery(): SpyCommentToCommentTagQuery
    {
        return SpyCommentToCommentTagQuery::create();
    }

    public function getCommentTagPropelQuery(): SpyCommentTagQuery
    {
        return SpyCommentTagQuery::create();
    }

    public function createCommentMapper(): CommentMapper
    {
        return new CommentMapper();
    }
}
