-- Column: categorie

-- ALTER TABLE mis.doctors DROP COLUMN categorie;

ALTER TABLE mis.doctors ADD COLUMN categorie integer;
COMMENT ON COLUMN mis.doctors.categorie IS '-- ��������� �����';

/* � ������� mis.degrees ��������: �������� ���� -> �.�.� , ������ ���� -> �.�.�*/