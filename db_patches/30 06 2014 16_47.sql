-- Column: deleted

-- ALTER TABLE mis.cancelled_greetings DROP COLUMN deleted;

ALTER TABLE mis.cancelled_greetings ADD COLUMN deleted integer;
ALTER TABLE mis.cancelled_greetings ALTER COLUMN deleted SET DEFAULT 0;
COMMENT ON COLUMN mis.cancelled_greetings.deleted IS '������� �� ������. ������� ��� ����, ����� ����� ���� �������� ������ �� ������� ����� �������� �����';