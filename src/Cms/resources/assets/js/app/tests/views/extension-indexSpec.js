describe("Extension Index", function() {

    var c = require('../../views/extension/index.vue');

    var p = require('./inc/pagination.js')(c);
    p.testPagination(c);

    it('should have data', function () {
        expect(typeof c.data).toBe('function');
    });

    it('should be have a null resource', function () {
        var defaultData = c.data();
        expect(defaultData.resource).toBe(null);
    });

});