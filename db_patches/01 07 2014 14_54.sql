-- Table: mis.rebinded_medcards

-- DROP TABLE mis.rebinded_medcards;

CREATE TABLE mis.rebinded_medcards
(
  id serial NOT NULL, -- ��������
  card_number character varying(100), -- ����� �����
  old_policy integer, -- ������ ����� ������
  new_policy integer, -- ����� ����� ������
  changing_timestamp timestamp without time zone, -- ���� ���������
  worker_id integer -- �� ���������, ������������ ��������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.rebinded_medcards
  OWNER TO postgres;
COMMENT ON TABLE mis.rebinded_medcards
  IS '��������������� ��������. ������������ ��� ����, ����� ����� ����� ����� ����� ���� ��������� � ������� ������';
COMMENT ON COLUMN mis.rebinded_medcards.id IS '��������';
COMMENT ON COLUMN mis.rebinded_medcards.card_number IS '����� �����';
COMMENT ON COLUMN mis.rebinded_medcards.old_policy IS '������ ����� ������';
COMMENT ON COLUMN mis.rebinded_medcards.new_policy IS '����� ����� ������';
COMMENT ON COLUMN mis.rebinded_medcards.changing_timestamp IS '���� ���������';
COMMENT ON COLUMN mis.rebinded_medcards.worker_id IS '�� ���������, ������������ ��������';

