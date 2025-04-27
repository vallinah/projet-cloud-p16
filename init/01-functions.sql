-- Générateur d'ID formaté (générique)
CREATE OR REPLACE FUNCTION generate_id(prefix TEXT, seq_name TEXT) 
RETURNS TEXT AS $$
DECLARE
    seq_value INT;
BEGIN
    EXECUTE format('SELECT nextval(%L)', seq_name) INTO seq_value;

    RETURN prefix || LPAD(seq_value::TEXT, 5, '0');  
END;
$$ LANGUAGE plpgsql;