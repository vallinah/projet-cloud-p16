using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace AspNet.Models
{
    [Table("users")]
    public class Users
    {
        [Key]
        [Column("user_id")]
        public string UserId { get; set; }

        [Column("first_name")]
        [Required]
        [StringLength(250)]
        public string FirstName { get; set; }

        [Column("last_name")]
        [Required]
        [StringLength(250)]
        public string LastName { get; set; }

        [Column("date_of_birth")]
        [Required]
        public DateTime DateOfBirth { get; set; }

        [Column("email")]
        [Required]
        [StringLength(250)]
        public string Email { get; set; }

        [Column("password")]
        [Required]
        [StringLength(250)]
        public string Password { get; set; }

        [Column("created_date")]
        [Required]
        public DateTime CreatedDate { get; set; }

        [Column("is_valid")]
        public bool IsValid { get; set; }

    }


}