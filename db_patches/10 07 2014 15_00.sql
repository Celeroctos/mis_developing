
CREATE TABLE mis.oms_types
(
  id serial NOT NULL, -- ��������
  tasu_id integer, -- ��� � ����
  name character varying(128) -- �������� ���� ������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.oms_types
  OWNER TO postgres;
COMMENT ON TABLE mis.oms_types
  IS '��� ���';
COMMENT ON COLUMN mis.oms_types.id IS '��������';
COMMENT ON COLUMN mis.oms_types.tasu_id IS '��� � ����';
COMMENT ON COLUMN mis.oms_types.name IS '�������� ���� ������';


INSERT INTO mis.oms_types(tasu_id,"name") VALUES (1,'���. ����� ���');
INSERT INTO mis.oms_types(tasu_id,"name") VALUES (2,'����� ���');
INSERT INTO mis.oms_types(tasu_id,"name") VALUES (3,'���������');
INSERT INTO mis.oms_types(tasu_id,"name") VALUES (4,'����������� � �����������');
INSERT INTO mis.oms_types(tasu_id,"name") VALUES (5,'����������');
