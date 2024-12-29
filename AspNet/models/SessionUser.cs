using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using AspNet.Models;

namespace Aspnet.Models
{
    [Table("session_users")]
    public class SessionUser
    {
        [Key]
        [Column("session_users_id")]
        public int SessionUsersId { get; set; }

        [Required]
        [Column("created_date")]
        public DateTime CreatedDate { get; set; }

        [Required]
        [Column("name")]
        [StringLength(150)]
        public string Name { get; set; }

        [Required]
        [Column("value")]
        [StringLength(250)]
        public string Value { get; set; }

        [Required]
        [Column("user_id")]
        [StringLength(50)]
        public string UserId { get; set; }

        [ForeignKey("UserId")]
        public virtual Users User { get; set; }
    }
}