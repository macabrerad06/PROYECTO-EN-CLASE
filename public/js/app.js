Ext.onReady(() => {
    const authorsPanel = createAuthorPanel();

    const booksPanel = createAuthorPanel();

    const mainCard = Ext.create('Ext.Panel',{
        region: 'center',
        layout: 'card',
        items: [authorsPanel, booksPanel]
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
                }
            
            ]
        }, mainCard]
    });
});