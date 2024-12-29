using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace Aspnet.Models
{
    [Table("session_config")]
    public class SessionConfig
    {
        [Key]
        [Column("session_config_id")]
        public int SessionConfigId { get; set; }

        [Column("minute_timeout")]
        [Required]
        public decimal MinuteTimeout { get; set; }

        [Required]
        [Column("tentative_max")]
        public int TentativeMax { get; set; }
    }
}




