-- Table: mis.logs

-- DROP TABLE mis.logs;

CREATE TABLE mis.logs
(
  id serial NOT NULL,
  user_id integer, -- ID ������������
  url text, -- ����� �������
  changedate date, -- ���� ��������
  changetime time without time zone, -- ����� ��������
  CONSTRAINT logs_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.logs
  OWNER TO moniiag;
COMMENT ON TABLE mis.logs
  IS '���� �������';
COMMENT ON COLUMN mis.logs.user_id IS 'ID ������������';
COMMENT ON COLUMN mis.logs.url IS '����� �������';
COMMENT ON COLUMN mis.logs.changedate IS '���� ��������';
COMMENT ON COLUMN mis.logs.changetime IS '����� ��������';