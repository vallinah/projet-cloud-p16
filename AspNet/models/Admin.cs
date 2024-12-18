using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
namespace Aspnet.Models
{

    [Table("admin")]
    public class Admin
    {
        [Key]
        [Column("id_admin")]
        [StringLength(150)]
        public string IdAdmin { get; set; }

        [Column("login")]
        [StringLength(250)]
        public string Login { get; set; }

        [Column("email")]
        [Required]
        [StringLength(250)]
        public string Email { get; set; }

        [Column("password")]
        [Required]
        [StringLength(250)]
        public string Password { get; set; }
    }

}