<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['query'])) {

            $query = $this->data['query'];
            $result = $this->user->searchUser($query);

            foreach($result as $user) {
                $user['id'] = (string)$user['_id'];
                $user['avatar'] = $this->user->getUserAvatar($user);
                unset($user['email']);
                unset($user['password']);
                unset($user['preferences']);
                unset($user['bio']);
                unset($user['location']);
                unset($user['website']);
                unset($user['twitter']);
                unset($user['instagram']);
                unset($user['_id']);
            }
            
            if (count($result) == 1) {
                $result = (array)$result[0];
            }

            return $this->response([
                'data' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};