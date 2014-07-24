CREATE TABLE mis.oms_statuses
(
  id serial NOT NULL, -- ��������
  tasu_id integer, -- ��� � ����
  name character varying(128) -- �������� �������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.oms_statuses
  OWNER TO postgres;
COMMENT ON TABLE mis.oms_statuses
  IS '������� �������';
COMMENT ON COLUMN mis.oms_statuses.id IS '��������';
COMMENT ON COLUMN mis.oms_statuses.tasu_id IS '��� � ����';
COMMENT ON COLUMN mis.oms_statuses.name IS '�������� �������';




INSERT INTO mis.oms_statuses(tasu_id,"name") VALUES (1,'�������');
INSERT INTO mis.oms_statuses(tasu_id,"name") VALUES (2,'�����');
INSERT INTO mis.oms_statuses(tasu_id,"name") VALUES (3,'�������');
INSERT INTO mis.oms_statuses(tasu_id,"name") VALUES (4,'�������������');