<?php

namespace App\Services;

use App\Models\BoardComment;
use App\Models\BoardWrite;
use App\Models\Inquiry;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function saveNotification($notify_type, $target_id, $target_id2 = null): void
    {
        $msg = ''; $receive_id = ''; $receive_nickname = ''; $send_id = ''; $send_nickname = ''; $refer_url = '';

        if ($notify_type === 'createComment'){

            $write = BoardWrite::find($target_id);
            $comment = BoardComment::find($target_id2);

            if (strlen($comment->comment) > 50) {
                $truncatedComment = mb_substr($comment->comment, 0, 46) . '...';
            }else{
                $truncatedComment = $comment->comment;
            }

            $msg = '‘' . $write->subject.'’에 ' . Auth::user()->nickname . '님의 댓글 ' . $truncatedComment;
            $refer_url = '/bbs/'.$write->table_id.'/read/'.$write->id;
            $receive_id = $write->user_id;
            $receive_nickname = $write->writer;
            $send_id = Auth::id();
            $send_nickname = Auth::user()->nickname;

        }elseif ($notify_type === 'createReplyComment') {

            $parent = BoardComment::find($target_id);
            $comment = BoardComment::find($target_id2);

            if (strlen($parent->comment) > 50) {
                $truncatedComment1 = mb_substr($parent->comment, 0, 46) . '...';
            }else{
                $truncatedComment1 = $parent->comment;
            }

            if (strlen($comment->comment) > 50) {
                $truncatedComment2 = mb_substr($comment->comment, 0, 46) . '...';
            }else{
                $truncatedComment2 = $comment->comment;
            }

            $msg = '‘' . $truncatedComment1.'’에 ' . Auth::user()->nickname . '님의 댓글 ' . $truncatedComment2;
            $refer_url = '/bbs/'.$comment->table_id.'/read/'.$comment->write_id;
            $receive_id = $parent->user_id;
            $receive_nickname = $parent->writer;
            $send_id = Auth::id();
            $send_nickname = Auth::user()->nickname;

        }elseif ($notify_type === 'writeDeleteStatusUpdate') {

            $data = BoardWrite::find($target_id);
            $truncatedComment = $data->subject;
            $write_id = $data->id;

            $msg = '작성하신 ‘' . $truncatedComment.'’은 사이트 정책에 맞지 않아 운영자에 의하여 삭제 처리되었습니다.';
            $refer_url = '/bbs/'.$data->table_id.'/read/'.$write_id;
            $receive_id = $data->user_id;
            $receive_nickname = $data->writer;
            $send_id = Auth::id();
            $send_nickname = Auth::user()->nickname;

        }elseif ($notify_type === 'commentDeleteStatusUpdate') {

            $data = BoardComment::with('write:id,table_id')->find($target_id);
            if (strlen($data->comment) > 50) {
                $truncatedComment = mb_substr($data->comment, 0, 46) . '...';
            }else{
                $truncatedComment = $data->comment;
            }
            $write_id = $data->write_id;

            $msg = '작성하신 ‘' . $truncatedComment.'’은 사이트 정책에 맞지 않아 운영자에 의하여 삭제 처리되었습니다.';
            $refer_url = '/bbs/'.$data->write->table_id.'/read/'.$write_id;
            $receive_id = $data->user_id;
            $receive_nickname = $data->writer;
            $send_id = Auth::id();
            $send_nickname = Auth::user()->nickname;

        }elseif ($notify_type === 'inquiryAnswer') {

            $inquiry = Inquiry::with('user:id,nickname')->find($target_id);
            
            $msg = '문의하신 ‘' . $inquiry->subject.'’에 답변이 등록되었습니다.';
            $refer_url = '/mypage/inquiry-list';
            $receive_id = $inquiry->user_id;
            $receive_nickname = $inquiry->user->nickname;
            $send_id = Auth::id();
            $send_nickname = Auth::user()->nickname;
            
        }

        $insertData = [
            'message' => $msg,
            'receive_id' => $receive_id,
            'receive_nickname' => $receive_nickname,
            'send_id' => $send_id,
            'send_nickname' => $send_nickname,
            'refer_url' => $refer_url,
        ];
        Notification::create($insertData);

    }
}
