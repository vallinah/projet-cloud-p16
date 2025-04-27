CREATE OR REPLACE VIEW v_mouvement_commission AS
SELECT 
    mc.id_mouvement_crypto,
    mc.nombre,
    mc.cours,
    mc.vente,
    mc.achat,
    mc.crypto_id,
    cm.name,
    mc.user_id,
    mc.id_commission,
    c.valeur AS commission_valeur,
   (c.valeur /100) * ( mc.cours * mc.nombre) AS total_commission,
    mc.date_mouvement
FROM mouvement_crypto mc
JOIN cryptocurrencies cm ON cm.crypto_id = mc.crypto_id
JOIN commission c ON mc.id_commission = c.id_commission;
