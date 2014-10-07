-- Table: mis.timetable_facts

-- DROP TABLE mis.timetable_facts;

CREATE TABLE mis.timetable_facts
(
  id serial NOT NULL, -- ��������
  is_range integer, -- ���� � ���, ��� ����� ������� ����������, � �� ����
  name character varying(150)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.timetable_facts
  OWNER TO postgres;
COMMENT ON TABLE mis.timetable_facts
  IS '������� ������������� ��� ����������';
COMMENT ON COLUMN mis.timetable_facts.id IS '��������';
COMMENT ON COLUMN mis.timetable_facts.is_range IS '���� � ���, ��� ����� ������� ����������, � �� ����';