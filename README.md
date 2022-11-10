# 包作用
简化你的关注/取消关注操作。

# 安装
```
composer require "jtar/hyperf-follow"

php bin/hyperf.php vendor:publish jtar/hyperf-follow

# 执行迁移(可能会破坏你的迁移文件,谨慎操作)
php bin/hyperf.php migrate

```
# 文档
包cv [overtrue/laravel-follow](https://github.com/overtrue/laravel-follow)的,模型事件未完全复制过来。

```php
    public function test()
    {
//        $user1 = User::find(14);
//        $user2 = User::find(15);


//        $user1->follow($user2);   关注
//        $user1->unfollow($user2); //  取消关注
//        $user1->toggleFollow($user2);   //  关注和取消关注切换

//        var_dump($user1->isFollowing($user2));    ...

//        var_dump($user1->isFollowedBy($user2));   ..,

//        var_dump($user2->hasRequestedToFollow($user1));   ...

//        var_dump($user1->followings); ...

//        var_dump($user2->followers()->count());   ...

//       $users = User::withCount(['followings', 'followables'])->get();    ...

//       return $this->success('',$users);
    }
```
[文档链接](https://github.com/overtrue/laravel-follow)