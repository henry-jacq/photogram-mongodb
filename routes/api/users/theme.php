<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['value'])) {

            $theme = $this->data['value'];
            $data = [
                'id' => $_SESSION['user'],
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
