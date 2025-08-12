const createBookPanel = () => {
    const authorStore = Ext.getStore('AuthorStore');
    if(!authorStore){
        // Create the book panel using the author store
        throw new Error('AuthorStore no encontrada');
    }
    Ext.define('App.model.Book', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id', type: "int"},
            {name: 'title', type: "string"},
            {name: 'description', type: "string"},
            {name: 'publicationDate', type: "date", dateFormat: 'Y-m-d'},
            {name: 'authorID', mapping: 'author.id', type: "int"},
            {name: 'authorName', convert:(v,rec) => {
                const a = rec.get("author");
                return a ? `${a.firstName} ${a.lastName}` : '';
                },
            },
            {name: 'isbn', type: "string"},
            {name: 'genre', type: "string"},
            {name: 'edition', type: "string"}
        ]
    });

    const BookStore = Ext.create('Ext.data.Store',{
        storeId: 'BookStore',
        model: 'App.model.Book',
        proxy: {
            type       : 'rest',
            url        : '/api/book',
            reader     : {type: 'json',rootProperty: ''},
            write: {type: 'json',rootProperty: '',writeAllFields: true},
            appendID: false
        },
        autoLoad: true,
        autoSync: false
    });

    return Ext.create('Ext.grid.Panel', {
        title: 'Books',
        store: BookStore,
        itemId: 'bookGrid',
        layout: 'fit',
        columns: [
            {
                text: 'ID',
                width: 40,
                sortable: false,
                hidable: false,
                dataIndex: 'id'
            },
            {
                text: 'Title',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'title'
            },
            {
                text: 'Description',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'description'
            },
            {
                text: 'Publication Date',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'publicationDate'
            },
            {
                text: 'Author',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'authorName'
            },
            {
                text: 'ISBN',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'isbn'
            },
            {
                text: 'Genre',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'genre'
            },
            {
                text: 'Edition',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'edition'
            }
        ]
    });
};

window.createBookPanel = createBookPanel;

