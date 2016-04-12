module.exports = function(c) {
  return {
    testPagination: function() {
      it('should be have empty items', function () {
          var defaultData = c.data();
          expect(defaultData.items.data).toEqual([]);
      });

      it('should be have last_page', function () {
          var defaultData = c.data();
          expect(defaultData.items.last_page).toEqual(1);
      });

      it('should be have current_page', function () {
          var defaultData = c.data();
          expect(defaultData.items.current_page).toEqual(1);
      });

      it('should be have total_pages', function () {
          var defaultData = c.data();
          expect(defaultData.items.total_pages).toEqual(1);
      });
    }
  };
};