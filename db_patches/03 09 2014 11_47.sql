-- Table: mis.timetable

-- DROP TABLE mis.timetable;

CREATE TABLE mis.timetable
(
  id serial NOT NULL, -- ��������
  date_begin date, -- ���� ������ �������� �������
  date_end date, -- ���� ����� �������� �������
  timetable_rules text -- �������, ������� �������� ���������� � ������� JSON
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.timetable
  OWNER TO postgres;
COMMENT ON TABLE mis.timetable
  IS '������� ���������� (��������) ������. ������ ���� ������. ������ (����������) �������� � ���� ���� � ������ ������. ������� - ��� ��������� ����� �������, ������������ �� ������ ������ ��������, ��� ������ ��� ���������. ������� �������� � ���� timetable_rules � ������ �������';
COMMENT ON COLUMN mis.timetable.id IS '��������';
COMMENT ON COLUMN mis.timetable.date_begin IS '���� ������ �������� �������';
COMMENT ON COLUMN mis.timetable.date_end IS '���� ����� �������� �������';
COMMENT ON COLUMN mis.timetable.timetable_rules IS '�������, ������� �������� ���������� � ������� JSON';
