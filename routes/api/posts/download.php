<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['id'])) {
            
            $id = $this->data['id'];
            
            $filePath = $this->post->getPostZip($id);
            
            if ($filePath) {
                $response = $this->response(
                    ['zipFile' => $filePath],
                    contentType: 'application/zip',
                    headers: [
                        'Content-Length' => filesize($filePath),
                        'Content-Disposition' => "attachment; filename=". basename($filePath)
                    ]
                );
                unlink($filePath);
                return $response;
            }
            return $this->response([
                'message' => false
            ], 404);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
