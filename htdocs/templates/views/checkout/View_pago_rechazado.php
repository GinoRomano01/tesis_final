<?php include INCLUDES . 'header_cliente.php'; ?>

<style>
:root {
    --lino: #f7f0e6; --papel: #fdfaf6; --borde: #d4c4aa;
    --caoba: #5c2d0a; --caoba2: #7a3e14; --amb: #b8722a;
    --tinta2: #4a3020; --g1: #8a7560; --rojo: #b53030;
}
.result-page {
    background: var(--lino);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    font-family: 'Source Sans 3', Georgia, sans-serif;
}
.result-card {
    background: var(--papel);
    border: 1.5px solid var(--borde);
    border-radius: 16px;
    padding: 3rem 2.5rem;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow: 0 4px 24px rgba(92,45,10,.08);
}
.result-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    background: #fee2e2;
    color: #dc2626;
}
.result-titulo {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem;
    font-weight: 700;
    color: var(--caoba);
    margin-bottom: .5rem;
}
.result-sub {
    font-size: .92rem;
    color: var(--tinta2);
    margin-bottom: 1.5rem;
    line-height: 1.65;
}
.btn-reintentar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .45rem;
    padding: .78rem;
    background: var(--caoba);
    color: #fff;
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .95rem;
    font-weight: 700;
    text-decoration: none;
    margin-bottom: .6rem;
    transition: background .15s;
    box-shadow: 0 2px 8px rgba(92,45,10,.25);
}
.btn-reintentar:hover { background: var(--caoba2); color: #fff; }
.btn-result-sec {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    padding: .7rem;
    background: none;
    border: 1.5px solid var(--borde);
    border-radius: 9px;
    font-family: 'Source Sans 3', sans-serif;
    font-size: .9rem;
    font-weight: 600;
    color: var(--caoba);
    text-decoration: none;
    transition: border-color .15s;
}
.btn-result-sec:hover { border-color: var(--rojo); color: var(--caoba); }
.result-nota {
    font-size: .8rem;
    color: var(--g1);
    margin-top: 1.2rem;
    line-height: 1.55;
}
</style>

<div class="result-page">
    <div class="result-card">

        <?= Toast::flash() ?>

        <div class="result-icon">
            <i class="fas fa-times"></i>
        </div>

        <div class="result-titulo">El pago no se procesó</div>

        <div class="result-sub">
            No se realizó ningún cobro. Podés intentarlo nuevamente
            con los mismos datos o usar otro medio de pago.
        </div>

        <a href="<?= URL ?>checkout" class="btn-reintentar">
            <i class="fas fa-redo"></i> Reintentar pago
        </a>

        <a href="<?= URL ?>carrito" class="btn-result-sec">
            <i class="fas fa-shopping-cart"></i> Volver al carrito
        </a>

        <div class="result-nota">
            Si el problema persiste, contactanos y te ayudamos
            a completar tu compra.
        </div>

    </div>
</div>

<?php include INCLUDES . 'footer_cliente.php'; ?>