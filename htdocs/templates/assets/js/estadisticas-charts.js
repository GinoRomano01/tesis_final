const SP_CHART = {
    serie:   ['#7a3e14', '#b8722a', '#2e6b3a', '#2f5f86', '#8c8a2e', '#8a7560'],
    texto:   '#6b5844',
    tinta:   '#2c1a0e',
    grid:    'rgba(92, 45, 10, .08)',
    surface: '#fdfaf6',
    borde:   '#d4c4aa',
    verde:   '#2e6b3a',
    rojo:    '#b53030',
    azul:    '#2f5f86',   

    alpha(hex, a) {
        const n = parseInt(hex.slice(1), 16);
        return `rgba(${(n >> 16) & 255}, ${(n >> 8) & 255}, ${n & 255}, ${a})`;
    },

    pesos(v) {
        return '$ ' + Number(v).toLocaleString('es-AR', { maximumFractionDigits: 0 });
    },

    fechaCorta(f) {
        const p = String(f).split('-');
        if (p.length === 3) return p[2] + '/' + p[1];
        if (p.length === 2) {
            const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
            return meses[parseInt(p[1], 10) - 1] + ' ' + p[0];
        }
        return f;
    },

    pesosCorto(v) {
        const n = Number(v);
        if (Math.abs(n) >= 1e6) return '$ ' + (n / 1e6).toLocaleString('es-AR', { maximumFractionDigits: 1 }) + ' M';
        if (Math.abs(n) >= 1e3) return '$ ' + (n / 1e3).toLocaleString('es-AR', { maximumFractionDigits: 0 }) + ' mil';
        return '$ ' + n.toLocaleString('es-AR');
    },

    cmp(label, valores, fechas, color, formato = 'num', extra = {}) {
        return Object.assign({
            type: 'line', label, data: valores, fechasCmp: fechas, formato,
            borderColor: color, borderWidth: 2, borderDash: [6, 4],
            backgroundColor: 'transparent', fill: false, tension: .35,
            pointRadius: 0, pointHoverRadius: 4, pointBackgroundColor: color,
            order: 0,
        }, extra);
    },

    cmpBarra(label, filas) {
        return {
            label, data: filas.map(r => parseFloat(r.cmp) || 0),
            backgroundColor: SP_CHART.alpha(SP_CHART.azul, .8), borderRadius: 4,
        };
    },

    tooltipLabel(ctx) {
        const ds = ctx.dataset;
        const v  = ds.formato === 'pesos' ? SP_CHART.pesos(ctx.parsed.y)
                                          : Number(ctx.parsed.y).toLocaleString('es-AR');
        const f  = ds.fechasCmp ? ds.fechasCmp[ctx.dataIndex] : null;
        return ' ' + ds.label + (f ? ' (' + SP_CHART.fechaCorta(f) + ')' : '') + ': ' + v;
    },
};

if (window.Chart) {
    Chart.defaults.color                = SP_CHART.texto;
    Chart.defaults.borderColor          = SP_CHART.grid;
    Chart.defaults.font.family          = "'Source Sans 3', Georgia, sans-serif";
    Chart.defaults.font.size            = 12;
    Chart.defaults.maintainAspectRatio  = false;
    Chart.defaults.plugins.legend.labels.boxWidth      = 12;
    Chart.defaults.plugins.legend.labels.boxHeight     = 12;
    Chart.defaults.plugins.legend.labels.padding       = 16;
    Chart.defaults.plugins.legend.labels.color         = SP_CHART.tinta;
    Chart.defaults.plugins.tooltip.backgroundColor     = SP_CHART.surface;
    Chart.defaults.plugins.tooltip.titleColor          = SP_CHART.tinta;
    Chart.defaults.plugins.tooltip.bodyColor           = SP_CHART.tinta;
    Chart.defaults.plugins.tooltip.borderColor         = SP_CHART.borde;
    Chart.defaults.plugins.tooltip.borderWidth         = 1;
    Chart.defaults.plugins.tooltip.padding             = 10;
    Chart.defaults.plugins.tooltip.boxPadding          = 4;
    Chart.defaults.plugins.tooltip.titleFont           = { weight: '600' };
}
