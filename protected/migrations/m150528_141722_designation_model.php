<?php

class m150528_141722_designation_model extends CDbMigration
{
	public function up()
	{
        $this->execute('CREATE TABLE "hospital"."designations" (
                    "id" int4 NOT NULL,
                    "drug_id" int4,
                    "use_id" int4,
                    "dosage" int4,
                    "per_day" int4,
                    "eat_interval" int4,
                    "eat_type" bool,
                    "date_end" date,
                    "comment" text COLLATE "default",
                    "date_begin" date
                    )
                    WITH (OIDS=FALSE)
                    ;');

        $this->execute('ALTER TABLE "hospital"."designations" OWNER TO "postgres";');
	}

	public function down()
	{
		$this->execute('DELETE TABLE "hospital"."designations"');
	}
}