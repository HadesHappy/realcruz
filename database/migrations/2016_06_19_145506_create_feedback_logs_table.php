<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('feedback_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('runtime_message_id')->nullable();
            $table->string('message_id')->nullable();
            $table->string('feedback_type');
            $table->text('raw_feedback_content');

            $table->timestamps();

            // không cần ràng buộc này, vì thậm chí khi gửi bằng AWS thì SNS feedback gần như ngay lập tức, trước khi ghi xuống tracking_logs
            //$table->foreign('message_id')->references('message_id')->on('tracking_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('feedback_logs');
    }
}
