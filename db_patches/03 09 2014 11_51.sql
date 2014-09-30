-- Table: mis.doctors_timetables

-- DROP TABLE mis.doctors_timetables;

CREATE TABLE mis.doctors_timetables
(
  id serial NOT NULL, -- �������� �������
  id_doctor integer, -- ������ �� �����
  id_timetable integer -- ������ �� ����������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.doctors_timetables
  OWNER TO postgres;
COMMENT ON TABLE mis.doctors_timetables
  IS '������� ������ ������ �� ������ �������� � ���������� (� ������� ��� ������� ��������� ���������� �� ������ ���������� �������) � � ���������� �������� ����� ���� ���� ����������';
COMMENT ON COLUMN mis.doctors_timetables.id IS '�������� �������';
COMMENT ON COLUMN mis.doctors_timetables.id_doctor IS '������ �� �����';
COMMENT ON COLUMN mis.doctors_timetables.id_timetable IS '������ �� ����������';

