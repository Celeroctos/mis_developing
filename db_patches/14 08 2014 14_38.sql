-- Column: is_empty

-- ALTER TABLE mis.medcard_records DROP COLUMN is_empty;

ALTER TABLE mis.medcard_records ADD COLUMN is_empty integer;
COMMENT ON COLUMN mis.medcard_records.is_empty IS '���� ������� �������. ���� 0 - �� � ������� ��� �� ������ ������������ ����, 1 - ���� ���� �� ���� ���� ����������� � �������';
