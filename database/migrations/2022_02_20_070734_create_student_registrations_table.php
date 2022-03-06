<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->year("start_year");
            $table->year("end_year");
            $table->date("open_date");
            $table->string("is_open", 1)->default("Y");
            $table->date('close_date')->nullable();
            $table->unsignedBigInteger("created_by");
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create("sch_school", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("address");
            $table->string("no_phone")->nullable();
            $table->string("no_zip")->nullable();
            $table->string("name_principle");
            $table->string("nik_principle");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create("sch_majors", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug");
            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create("sch_expertise", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("slug");
            $table->text("description")->nullable();
            $table->unsignedBigInteger("major_id");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sch_students', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("born_place");
            $table->date("dob");
            $table->string("old_school");
            $table->string("no_nisn");
            $table->string("no_nik");
            $table->string("gender");
            $table->text("address");
            $table->unsignedBigInteger("major_id");
            $table->unsignedBigInteger("expertise_id");
            $table->string("no_phone");
            $table->string("hasKIP", 1)->default("N");
            $table->string("name_father")->nullable();
            $table->string("nik_father")->nullable();
            $table->string("address_father")->nullable();
            $table->string("name_mother")->nullable();
            $table->string("nik_mother")->nullable();
            $table->string("address_mother")->nullable();
            $table->string("agree_tos", 1)->default("N");
            $table->string("is_file_complete", 1)->default("N");
            $table->string("is_active", 1)->default('Y');
            $table->string("is_graduate", 1)->default('N');
            $table->dateTime("date_graduate")->nullable();
            $table->string("approval_step")->default("P");
            $table->unsignedBigInteger("registration_id");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("registration_code");
            $table->boolean('enabled')->default(1);
            $table->timestamps();

            /**
             * Approval Step
             * P = pending
             * R = Reject
             * G = Re registration
             * A = Approve
             */
        });

        Schema::create('sch_report_cards', function (Blueprint $table) {
            $table->id();
            $table->string("lesson");
            $table->double("semester_1", 20, 4)->default(0);
            $table->double("semester_2", 20, 4)->default(0);
            $table->double("semester_3", 20, 4)->default(0);
            $table->double("semester_4", 20, 4)->default(0);
            $table->double("semester_5", 20, 4)->default(0);
            $table->double("semester_6", 20, 4)->default(0);
            $table->string("type")->default("registration");
            $table->unsignedBigInteger("student_id");
            $table->timestamps();
        });

        Schema::create('sch_list_schools', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string('type')->default('primary');
            $table->timestamps();
        });

        Schema::create('sch_student_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("no_bird_card");
            $table->unsignedBigInteger('religion_id');
            $table->unsignedBigInteger('special_need_id');
            $table->string('nationality');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->char('district_id', 7);
            $table->char('village_id', 10);
            $table->string('village_name');
            $table->string('rt_name');
            $table->string('rw_name');
            $table->string('zip_code', 20);
            $table->unsignedBigInteger('residence_id');
            $table->unsignedBigInteger('transportation_id');
            $table->tinyInteger("family_order");
            $table->unsignedBigInteger('blood_group_id');
            $table->string("home_phone", 20)->nullable();
            $table->string("email", 100)->nullable();
            $table->unsignedBigInteger("extracurricular_id");
            $table->decimal("height", 6, 2);
            $table->decimal("weight", 6, 2);
            $table->decimal("school_home_distance", 6, 2);
            $table->decimal("travel_time", 6, 2);

            // Help
            $table->string("kks_no", 100)->nullable();
            $table->string("is_kps_receiver", 1)->default('N');
            $table->string("kps_no", 100)->nullable();
            $table->string("is_pip_worthy", 1)->default('N');
            $table->string("pip_worthy_reason")->nullable();
            $table->string("pip_no", 100)->nullable();
            $table->string("pip_name", 100)->nullable();
            $table->string("is_kip_receiver", 1)->default('N');
            $table->string("is_kip_physical_receiver", 1)->default('N');

            // Parent
            $table->string("father_born_place", 200)->nullable();
            $table->date("father_dob")->nullable();
            $table->string("father_education")->nullable();
            $table->string("father_job")->nullable();
            $table->string("father_income")->nullable();
            $table->string("father_special_need")->nullable();

            $table->string("mother_born_place", 200)->nullable();
            $table->date("mother_dob")->nullable();
            $table->string("mother_education")->nullable();
            $table->string("mother_job")->nullable();
            $table->string("mother_income")->nullable();
            $table->string("mother_special_need")->nullable();

            $table->string("guardian_parent_born_place", 200)->nullable();
            $table->date("guardian_parent_dob")->nullable();
            $table->string("guardian_parent_education")->nullable();
            $table->string("guardian_parent_job")->nullable();
            $table->string("guardian_parent_income")->nullable();
            $table->string("guardian_parent_special_need")->nullable();

            $table->string("is_details_complete")->default("N");
            $table->string("is_help_complete")->default("N");
            $table->string("is_parent_complete")->default("N");

            $table->tinyInteger('sibling_number')->nullable();
            $table->decimal('head_circumference', 10, 2)->nullable();
            $table->string('guardian_parent_name')->nullable();
            $table->string('guardian_parent_nik')->nullable();
            $table->timestamps();
        });

        Schema::create('sch_time_lines', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('text');
            $table->string('description')->nullable();
            $table->date('year');
            $table->timestamps();
        });

        Schema::create('sch_special_needs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_residence', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_transportation', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_blood_groups', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_extracurricular', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_kip_worthy_reason', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_school_grade', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_income', function (Blueprint $table) {
            $table->id();
            $table->string("value");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_parent_jobs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });

        Schema::create('sch_religions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("status", 1)->default('Y');
            $table->timestamps();
        });

        Schema::create('home_data', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('student_registrations');
        Schema::dropIfExists('sch_school');
        Schema::dropIfExists('sch_majors');
        Schema::dropIfExists('sch_expertise');
        Schema::dropIfExists('sch_students');
        Schema::dropIfExists('sch_report_cards');
        Schema::dropIfExists('sch_list_schools');
        Schema::dropIfExists('sch_student_details');
        Schema::dropIfExists('sch_time_lines');
        //Schema::dropIfExists('sch_files');
        Schema::dropIfExists('sch_special_needs');
        Schema::dropIfExists('sch_residence');
        Schema::dropIfExists('sch_transportation');
        Schema::dropIfExists('sch_blood_groups');
        Schema::dropIfExists('sch_extracurricular');
        Schema::dropIfExists('sch_kip_worthy_reason');
        Schema::dropIfExists('sch_schools');
        Schema::dropIfExists('sch_income');
        Schema::dropIfExists('sch_parent_jobs');
        Schema::dropIfExists('sch_religions');
        Schema::dropIfExists('sch_home_data');
    }
};
