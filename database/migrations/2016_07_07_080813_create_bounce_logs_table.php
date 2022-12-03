<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBounceLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bounce_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('runtime_message_id')->nullable();
            $table->string('message_id')->nullable();
            $table->string('bounce_type');
            $table->text('raw');

            $table->timestamps();

            // không cần ràng buộc này, vì thậm chí khi gửi bằng AWS thì SNS bounce gần như ngay lập tức, trước khi ghi xuống tracking_logs
            // $table->foreign('message_id')->references('message_id')->on('tracking_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('bounce_logs');
    }
}
