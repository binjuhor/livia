<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Issues\IssueType;
use App\Issues\IssueStatus;

class AddIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('summary')->nullable();
            $table->string('jira_key')->unique();
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unsignedFloat('story_point')->default(0);
            $table->enum('type', IssueType::getValues())
                  ->default(IssueType::Story);
            $table->enum('status', IssueStatus::getValues())
                  ->default(IssueStatus::ToDo);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeign('issues_project_id_foreign');
            $table->dropIfExists();
        });
    }
}
