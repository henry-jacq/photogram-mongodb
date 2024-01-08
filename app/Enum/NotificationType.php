<?php

declare(strict_types=1);

namespace App\Enum;

enum NotificationType: int
{
    case Likes = 1;
    case Deleted = 2;
    case Follows = 3;
    case Comment = 4;

    public static function isDelete(int $delete): bool
    {
        return self::tryFrom($delete) == self::Deleted;
    }

    public static function isFollow(int $follow): bool
    {
        return self::tryFrom($follow) == self::Follows;
    }

    public static function isLike(int $like): bool
    {
        return self::tryFrom($like) == self::Likes;
    }

    public static function isComment(int $comment): bool
    {
        return self::tryFrom($comment) == self::Comment;
    }
}