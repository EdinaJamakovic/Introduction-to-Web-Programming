window.Utils = (function() {
  return {
    datatable: function(table_id, columns, data, pageLength=15) {
      if ($.fn.dataTable.isDataTable("#" + table_id)) {
         $("#" + table_id)
           .DataTable()
           .destroy();
       }
       $("#" + table_id).DataTable({
         data: data,
         columns: columns,
         pageLength: pageLength,
         lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
       });
    },
    parseJwt: function(token) {
if (!token) return null;
       try {
         const payload = token.split('.')[1];
         const decoded = atob(payload);
         return JSON.parse(decoded);
       } catch (e) {
         console.error("Invalid JWT token", e);
         return null;
       }
    },
     hasRole: function(requiredRole) {
        const token = localStorage.getItem("user_token");
        if (!token) return false;
        
        const decoded = this.parseJwt(token);
        return decoded && decoded.user && decoded.user.role === requiredRole;
    },

    hasAnyRole: function(requiredRoles) {
        const token = localStorage.getItem("user_token");
        if (!token) return false;
        
        const decoded = this.parseJwt(token);
        if (!decoded || !decoded.user || !decoded.user.role) return false;
        
        return requiredRoles.includes(decoded.user.role);
    },
    getCurrentUser: function() {
        const token = localStorage.getItem("user_token");
        if (!token) return null;
        
        const decoded = this.parseJwt(token);
        return decoded ? decoded.user : null;
    },

    isTokenExpired: function(token) {
        if (!token) return true;
        
        const decoded = this.parseJwt(token);
        if (!decoded || !decoded.exp) return true;
        
        return decoded.exp * 1000 < Date.now();
    }
  };
})();
