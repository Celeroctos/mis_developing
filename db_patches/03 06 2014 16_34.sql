-- Table: mis.comments_oms

-- DROP TABLE mis.comments_oms;

CREATE TABLE mis.comments_oms
(
  id integer NOT NULL DEFAULT nextval('mis.medcard_comments_id_seq'::regclass), -- ��������
  comment text, -- ��� ����� �����������
  id_oms integer, -- ������ �� ���
  create_date timestamp without time zone, -- ���� � �����, ����� ����������� ��� ������
  employer_id integer -- �� ���������, ������� ������ ������ �������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.comments_oms
  OWNER TO postgres;
COMMENT ON TABLE mis.comments_oms
  IS '����������� � ���������, ������� ����� ����';
COMMENT ON COLUMN mis.comments_oms.id IS '��������';
COMMENT ON COLUMN mis.comments_oms.comment IS '��� ����� �����������';
COMMENT ON COLUMN mis.comments_oms.id_oms IS '������ �� ���';
COMMENT ON COLUMN mis.comments_oms.create_date IS '���� � �����, ����� ����������� ��� ������';
COMMENT ON COLUMN mis.comments_oms.employer_id IS '�� ���������, ������� ������ ������ �������';
