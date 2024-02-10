<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['theme'])) {

            $theme = $this->data['theme'];
            $data = [
                'id' => $this->getUserId(),
                'theme' => $theme
            ];

            $result = $this->user->setTheme($data);

            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
