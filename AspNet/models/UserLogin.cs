using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Aspnet.Models
{
    [Table("user_login")]
    public class UserLogin
    {
        [Key]
        [Column("user_login_id")]
        public int UserLoginId { get; set; }

        [Required]
        [Column("user_id")]
        [StringLength(50)]
        public string UserId { get; set; }

        [Required]
        [Column("tentative")]
        public int Tentative { get; set; }

        [Column("email")]
        [StringLength(255)]
        public string Email { get; set; }
    }
}