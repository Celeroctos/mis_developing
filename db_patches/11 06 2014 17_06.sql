-- Table: mis.medcard_records

-- DROP TABLE mis.medcard_records;

CREATE TABLE mis.medcard_records
(
  id serial NOT NULL, -- ��������
  medcard_id character varying, -- ����� ��������
  greeting_id integer, -- ����� �����
  record_id integer, -- ����� ������ � ����� � �����
  template_name text, -- ��� �������
  doctor_id integer, -- ������ �� �����
  record_date timestamp without time zone, -- ���� ����������
  template_id integer -- �� �������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.medcard_records
  OWNER TO postgres;
COMMENT ON TABLE mis.medcard_records
  IS '������ � ��������. ������ ������ ������������ ������ �������';
COMMENT ON COLUMN mis.medcard_records.id IS '��������';
COMMENT ON COLUMN mis.medcard_records.medcard_id IS '����� ��������';
COMMENT ON COLUMN mis.medcard_records.greeting_id IS '����� �����';
COMMENT ON COLUMN mis.medcard_records.record_id IS '����� ������ � ����� � �����';
COMMENT ON COLUMN mis.medcard_records.template_name IS '��� �������';
COMMENT ON COLUMN mis.medcard_records.doctor_id IS '������ �� �����';
COMMENT ON COLUMN mis.medcard_records.record_date IS '���� ����������';
COMMENT ON COLUMN mis.medcard_records.template_id IS '�� �������';

