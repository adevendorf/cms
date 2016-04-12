describe("User Edit", function() {

    var c = require('../../views/user/edit.vue');
    
    it('should have data', function () {
        expect(typeof c.data).toBe('function');
    });
    
});