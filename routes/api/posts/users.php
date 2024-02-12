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

    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
