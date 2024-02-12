<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {

        // To fetch liked users
        if ($this->paramsExists(['likes'])) {

            $pid = $this->data['likes'];
            
            $data = $this->post->getLikedUsers($pid);

            $output = [];

            foreach ($data as $userId => $user) {
                $output[] = [
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'avatar' => $this->user->getUserAvatar($user),
                ];
            }

            $msg = count($output) > 0 ? true : false;

            return $this->response([
                'message' => $msg,
                'users' => $output
            ], 200);
        }

        // To fetch commented users
        if ($this->paramsExists(['comments'])) {
            
            $pid = $this->data['comments'];
            $user = (array) $this->user->getUser();

            $comments = $this->post->fetchComments($pid);

            $uids = array_column($comments, 'uid');

            $usersData = $this->post->getUsersByIds($uids);


            // Populate comments array
            $commentData = [];
            foreach ($comments as $comment) {
                $userData = $usersData[$comment['uid']];
                $commentData[] = [
                    'comment_id' => $comment['_id'],
                    'comment' => $comment['text'],
                    'timestamp' => $this->post->getHumanTime($comment['timestamp']),
                    'username' => $userData['username'],
                    'fullname' => $userData['fullname'],
                    'avatar' => $this->user->getUserAvatar($userData)
                ];
            }

            dd($commentData);
            
            
            
            $data = [
                'message' => true,
                'owner' => [
                    'username' => $user['username'],
                    'avatar' => $this->user->getUserAvatar($user)
                ],
                'comments' => [
                    [
                        'comment_id' => '',
                        'comment' => '',
                        'timestamp' => '',
                        'username' => '',
                        'fullname' => '',
                        'avatar' => ''
                    ]
                ]
            ];
            
            return $this->response($data, 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
