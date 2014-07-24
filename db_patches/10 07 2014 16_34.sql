-- Table: mis.insurances_regions

-- DROP TABLE mis.insurances_regions;

CREATE TABLE mis.insurances_regions
(
  id serial NOT NULL, -- ��������
  insurance_id integer, -- ������ �� ��������� ��������
  region_id integer -- ������ �� ������
)
WITH (
  OIDS=FALSE
);
ALTER TABLE mis.insurances_regions
  OWNER TO postgres;
COMMENT ON TABLE mis.insurances_regions
  IS '������� ����� ��������� �������� � ��������';
COMMENT ON COLUMN mis.insurances_regions.id IS '��������';
COMMENT ON COLUMN mis.insurances_regions.insurance_id IS '������ �� ��������� ��������';
COMMENT ON COLUMN mis.insurances_regions.region_id IS '������ �� ������';