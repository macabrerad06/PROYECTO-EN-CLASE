Ext.onReady(() => {
    const authorsPanel = createAuthorPanel();

    const booksPanel = createBooksPanel();

    const articlePanel = createArticlesPanel();

    const mainCard = Ext.create('Ext.Panel',{
        region: 'north', // Esta es la configuración que lo pone arriba
        xtype: 'toolbar', // El tipo de componente
        items: [authorsPanel, booksPanel, articlePanel]
    });

    Ext.create('Ext.container.Viewport', {
        id: "mainViewport",
        layout: 'border',
        items: [{
            region: 'north',
            type: 'toolbar',
            items: [
                {
                    text: 'Authors',
                    handler: ()=>mainCard.getLayout().setActiveItem(authorsPanel)
                },
                {
                    text: 'Books',
                    handler: ()=>mainCard.getLayout().setActiveItem(booksPanel)
                },
                {
                    text: 'Articles',
                    handler: ()=>mainCard.getLayout().setActiveItem(articlePanel)
                }
            
            ]
        }, mainCard]
    });
});