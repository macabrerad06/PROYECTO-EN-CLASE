const createAuthorPanel = () => {
    Ext.define('App.model.Author',{
        extends: 'Ext.data.Model',
        fields: [
            {name: 'id', type: "int"},
            {name: 'firstName',     type: "string"},
            {name: 'lastName',    type: "string"},
            {name: 'username',      type: "string"},
            {name: 'email',         type: "string"},
            {name: 'password',      type: "string"},
            {name: 'orcid',         type: "string"},
            {name: 'affiliation',   type: "string"}
        ]
    });

    let AuthorStore = Ext.create('Ext.data.Store',{
        storeId: 'AuthorStore',
        model: 'App.model.Author',
        proxy: {
            type       : 'rest',
            url        : '/api/author',
            reader     : {type: 'json',rootProperty: ''},
            write: {type: 'json',rootProperty: '',writeAllFields: true},
            appendID: false
        },
        autoLoad: true,
        autoSync: false
    });

    const grid = Ext.create('Ext.grid.Panel', {
        title: 'Authors',
        store: AuthorStore,
        itemId: 'authorGrid',
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
                text: 'First Name',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'firstName'
            },
            {
                text: 'Last Name',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'lastName'
            },
            {
                text: 'Username',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'username'
            },
            {
                text: 'Email',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'email'
            },
            {
                text: 'ORCID',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'orcid'
            },
            {
                text: 'Affiliation',
                flex: 1,
                sortable: false,
                hidable: false,
                dataIndex: 'affiliation'
            }
        ],
        renderTo: Ext.getBody()
    });
    return grid;
};